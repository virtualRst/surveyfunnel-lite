import React, { useContext, useEffect, useState } from "react";
import { BuildContext } from "../Context/BuildContext";
import fetchData from "../../HelperComponents/fetchData";
import { DesignContext } from "../Context/DesignContext";
import { ConfigureContext } from "../Context/ConfigureContext";
import { convertToRgbaCSS, designBackground } from "../../HelperComponents/HelperFunctions";
const { applyFilters } = wp.hooks;

function validateEmail(email) {
	const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(String(email).toLowerCase());
}

const currentlyPreviewing = true;

let __defaultResultScreen = [{
	title: 'Thank you for your submission',
	description: 'Complete the survey by click on "Complete Survey" button',
	id: '__defaultResultScreen',
	componentName: 'ResultScreen',
	type: 'RESULT_ELEMENTS',
	currentlySaved: true,
}];

let globalTotalScore = {
	score: 0,
};

let contentElementsLastIndex = 0;

let initialState = [];

let conditional_stack = [0];

export default function DesignPreview() {
    const { List, type: surveyType, proActive } = useContext(BuildContext);
    const designCon = useContext(DesignContext);
    const [currentTab, setCurrentTab] = useState(0);
    const [tabCount, setTabCount] = useState(0);
    const [componentList, setComponentList] = useState([]);
	const [error, setError] = useState([]);
	const { proSettings } = useContext(ConfigureContext);

    useEffect(() => {
		const { CONTENT_ELEMENTS } = List;
		let proQuestions = [];
		for(let i = 0; i < CONTENT_ELEMENTS.length; i++) {
			if ( ( CONTENT_ELEMENTS[i].componentName === 'TextElement' || CONTENT_ELEMENTS[i].componentName === 'ImageQuestion' ) && ! proActive ) {
				continue;
			}
			proQuestions.push( CONTENT_ELEMENTS[i] );
		}

        setComponentList([
            ...List.START_ELEMENTS,
            ...proQuestions,
            ...List.RESULT_ELEMENTS,
			...__defaultResultScreen
        ]);
        setTabCount(
            List.START_ELEMENTS.length +
			proQuestions.length +
			List.RESULT_ELEMENTS.length + 1
        );
		contentElementsLastIndex = List.START_ELEMENTS.length + proQuestions.length - 1;
        initialState = [
            ...List.START_ELEMENTS,
            proQuestions,
            ...List.RESULT_ELEMENTS,
			...__defaultResultScreen
        ];
    }, [List]);

    const changeCurrentTab = function (num, status = 'next') {
		// check for validations
		if ( ! checkValidations(num) || currentTab + num >= tabCount || ( componentList[currentTab].type === 'RESULT_ELEMENTS'  && num !== -1 ) ) {
			return;
		}
		let temp = num;
		let surveyTypeFlag = false;
		num = applyFilters( 'changeCurrentTabAsPerSurveyType', num, surveyType, componentList, currentTab, globalTotalScore );
        if( num !== -1 ) {
            num = applyFilters( 'changeCurrentTabAsPerConditionalLogic', num, componentList, currentTab );
        }
		if ( num !== temp ) {
			surveyTypeFlag = true;
		}
		if ( surveyTypeFlag && num > 0 ) {
            conditional_stack.push(num);
			setCurrentTab(num);
		}
		else if( componentList[currentTab].type !== 'RESULT_ELEMENTS' ) {
            let newTab = currentTab + num;
            if(status === 'prev') {
                conditional_stack.pop();
                newTab = conditional_stack[conditional_stack.length - 1];
            }
            else {
                conditional_stack.push(newTab);
            }
			setCurrentTab(newTab);
		}
		else {
            let newTab = contentElementsLastIndex;
            if(status === 'prev') {
                conditional_stack.pop();
                newTab = conditional_stack[conditional_stack.length - 1];
            }
            else{
                newTab = contentElementsLastIndex;
                conditional_stack.push(contentElementsLastIndex);
            }
           
			setCurrentTab(newTab);
		}
    };

	const checkValidations = ( num, disablity = false ) => {
		if ( currentlyPreviewing ) {
			return true;
		}

        if ( num === -1 ) {
            return true;
        }
        
		let error = [];
		switch ( componentList[currentTab].componentName ) {
			case 'CoverPage':
			case 'ResultScreen':
				break;
			case 'FormElements':
				let List = componentList[currentTab].List;
				List.map(function(item, i) {
					switch( item.componentName ) {
						case 'FirstName':
						case 'LastName':
                        case 'ShortTextAnswer':
                        case 'LongTextAnswer':
							if( item.required ) {
                                // do validation.
                                if ( item.value === '' ) {
                                    error.push(<p key={error.length}>{item.name} cannot be empty</p>)
                                }
                            }
							break;
                        case 'Email':
                            if (item.required) {
                                if ( item.value === '' ) {
                                    error.push(<p key={error.length}>{item.name} cannot be empty</p>)
                                }
                                else if ( ! validateEmail(item.value) ) {
                                    error.push(<p key={error.length}>Not a valid email!</p>)
                                }
                            }
                            break;
					}
				})
                break;
            case 'MultiChoice':
                const {answers} = componentList[currentTab];
                let flag = false;
                for(let i = 0; i < answers.length ; i++) {
                    if ( answers[i].checked ) {
                        flag = true;
                        break;
                    }
                }
                if ( ! flag ) {
                    error.push( <p key={error.length}>Please select atleast one answer!</p> );
                }
                break;
            case 'SingleChoice':
                if ( componentList[currentTab].value === '' ) {
                    error.push( <p key={error.length}>Please select atleast one answer!</p> );
                }
                break;
		}
		if ( error.length > 0 ) {
            if ( ! disablity ) {
                setError(error);
            }
			return false;
		}
        if ( ! disablity ) {
            setError([]);
        }
		return true;
	}

    const renderContentElements = (item, display = "none", idx) => {
        let style = {
            display,
        };
        switch (item.componentName) {
            case "SingleChoice":
                return (
                    <div className="surveyfunnel-lite-tab-SingleChoice"
                        style={{ ...style }}
                        key={item.id}
                    >
                        <div className="tab-container">
                            <div
                                className="tab"
                                key={item.id}
                                tab-componentname={item.componentName}
                            >
                                <h3 className="surveyTitle">{item.title}</h3>
                                <p className="surveyDescription">{item.description}</p>
        
                                <div className="radio-group">
                                    {item.answers.map(function (ele, i) {
                                        return (
                                            <div key={item.id + "_radio" + "_" + i} style={{ border: `1px solid ${convertToRgbaCSS(designCon.answerBorderColor)}` }} className="surveyfunnel-lite-tab-answer-container">
                                                <input
                                                    type="radio"
                                                    name={item.id + "_radio"}
                                                    id={item.id + "_radio" + "_" + i}
                                                    value={ele.name}
                                                    onChange={handleRadioChange}
                                                    listidx={idx}
                                                    inputidx={i}
                                                    checked={item.value === ele.name}
                                                />
                                                <label
                                                    htmlFor={
                                                        item.id + "_radio" + "_" + i
                                                    }
                                                >
                                                    <div>
                                                        { parseInt(i)+1}
                                                    </div>
                                                    <p>
                                                        {ele.name}
                                                     </p>

                                                </label>
                                            </div>
                                        );
                                    })}
                                </div>
								{ checkValidations( 1, true ) && <div className="nextButtonChoices">
                                    <button type="button" style={{
                                        background: convertToRgbaCSS(
                                            designCon.buttonColor
                                        ),
                                        color: convertToRgbaCSS(
                                            designCon.buttonTextColor
                                        ),
                                    }} onClick={() => {changeCurrentTab(1);}}>Next</button>
								</div>}
                            </div>
                        </div>
                    </div>
                );
            case "MultiChoice":
                return (
                    <div className="surveyfunnel-lite-tab-MultiChoice"
                    style={{ ...style }}
                    key={item.id}
                    >
                        <div
                            className="tab-container"
                            key={item.id}
                        >
                            <div
                                className="tab"
                                key={item.id}
                                tab-componentname={item.componentName}
                            >
                                <h3 className="surveyTitle">{item.title}</h3>
                                <p className="surveyDescription">{item.description}</p>
        
                                <div className="checkbox-group">
                                    {item.answers.map(function (ele, i) {
                                        return (
                                            <div key={item.id + "_checkbox" + "_" + i} style={{ border: `1px solid ${convertToRgbaCSS(designCon.answerBorderColor)}` }} className="surveyfunnel-lite-tab-answer-container">
                                                <input
                                                    type="checkbox"
                                                    name={item.id + "_checkbox"}
                                                    id={item.id + "_checkbox" + "_" + i}
                                                    value={ele.name}
                                                    listidx={idx}
                                                    inputidx={i}
                                                    checked={ele.checked}
                                                    onChange={handleCheckboxChange}
                                                />
                                                <label
                                                    htmlFor={
                                                        item.id + "_checkbox" + "_" + i
                                                    }
                                                >
                                                    <div>
                                                        { parseInt(i)+1}
                                                    </div>
                                                    <p>
                                                        {ele.name}
                                                     </p>
                                                </label>
                                            </div>
                                        );
                                    })}
                                </div>
								{ checkValidations( 1, true ) && <div className="nextButtonChoices">
                                    <button type="button" style={{
                                        background: convertToRgbaCSS(
                                            designCon.buttonColor
                                        ),
                                        color: convertToRgbaCSS(
                                            designCon.buttonTextColor
                                        ),
                                    }} onClick={() => {changeCurrentTab(1);}}>Next</button>	
								</div>}
                            </div>
                        </div>
                    </div>
                );
            case "CoverPage":
                return (
                    <div className="surveyfunnel-lite-tab-CoverPage"
                    style={{ ...style }}
                    key={item.id}
                    >
                        <div
                            className="tab-container"
                            key={item.id}

                        >
                            <div className="tab" tab-componentname={item.componentName}>
                                <h3 className="surveyTitle">{item.title}</h3>
                                <p className="surveyDescription">{item.description}</p>
								{applyFilters( 'renderPrivacyPolicy', '', item, proSettings, require('../Build/BuildImages/checkmark.png') )}                        
								<button type="button" className="surveyButton" style={{ background: convertToRgbaCSS(designCon.buttonColor), color: convertToRgbaCSS(designCon.buttonTextColor) }} onClick={() => {
                                    changeCurrentTab(1);
                                }}>
                                    {item.button}
                                </button>
                            </div>
                        </div>
                    </div>
                );
                case "ShortAnswer":
                case "LongAnswer":
                    return (
                        <div className={"surveyfunnel-lite-tab-" + item.componentName}
                        style={{ ...style }}
                        key={item.id}
                        >
                            <div
                                className="tab-container"
                                key={item.id}
    
                            >
                                <div className="tab" tab-componentname={item.componentName}>
                                    <h3 className="surveyTitle">{item.title}</h3>
                                    <p className="surveyDescription">{item.description}</p>
                                    {item.componentName === 'ShortAnswer' && <input style={{ border: `1px solid ${convertToRgbaCSS(designCon.answerBorderColor)}` }} type="text" />}
                                    {item.componentName === 'LongAnswer' && <textarea style={{ border: `1px solid ${convertToRgbaCSS(designCon.answerBorderColor)}` }} />}
                                    <button type="button" className="surveyButton" style={{ background: convertToRgbaCSS(designCon.buttonColor), color: convertToRgbaCSS(designCon.buttonTextColor) }} onClick={() => {
                                        changeCurrentTab(1);
                                    }}>
                                        Next
                                    </button>
                                </div>
                            </div>
                        </div>
                    );
            case "ResultScreen":
                return (
                    <div className="surveyfunnel-lite-tab-ResultScreen"
                    style={{ ...style }}
                    key={item.id}
                    >
                        <div
                            className="tab-container"
                            key={item.id}
                        >
                            <div className="tab" tab-componentname={item.componentName}>
								{applyFilters( 'renderResultScreen', '', item, surveyType, globalTotalScore )}
                                <h3 className="surveyTitle">{item.title}</h3>
                                <p className="surveyDescription">{item.description}</p>
                            </div>
                        </div>
                    </div>
                );
            case 'FormElements':
                return (
                    <div className="surveyfunnel-lite-tab-FormElements"
                    style={{ ...style }}
                    key={item.id}
                    >
                        <div
                            className="tab-container"
                            key={item.id}
                        >
                            <div className="tab" tab-componentname={item.componentName}>
                                <h3 className="surveyTitle">{item.title}</h3>
                                <p className="surveyDescription">{item.description}</p>
                                {item.List.map(function(ele, i) {
                                    switch( ele.componentName ) {
                                        case 'FirstName':
                                        case 'LastName':
                                        case 'Organisation':
                                        case 'ShortTextAnswer':
                                            return <div key={ele.id + '_' + i + 'key'}>
                                                <label>{ele.name} {ele.required ? '*' : ''}</label>
                                                <input type="text" id={ele.id + '_' + i} style={{ border: `1px solid ${convertToRgbaCSS(designCon.answerBorderColor)}` }} placeholder={ele.placeholder} required={ele.required} value={ele.value} onChange={handleChange} inputidx={i} listidx={idx} />
                                            </div>
                                        case 'Phone':
                                            return <div key={ele.id + '_' + i + 'key'}>
                                                <label>{ele.name} {ele.required ? '*' : ''}</label>
                                                <input type="tel" id={ele.id + '_' + i} style={{ border: `1px solid ${convertToRgbaCSS(designCon.answerBorderColor)}` }} placeholder={ele.placeholder} required={ele.required} value={ele.value} onChange={handleChange} inputidx={i} listidx={idx}/>
                                            </div>
                                        case 'Email':
                                            return <div key={ele.id + '_' + i + 'key'}>
                                                <label>{ele.name} {ele.required ? '*' : ''}</label>
                                                <input type="email" id={ele.id + '_' + i} style={{ border: `1px solid ${convertToRgbaCSS(designCon.answerBorderColor)}` }} placeholder={ele.placeholder} required={ele.required} value={ele.value} onChange={handleChange} inputidx={i} listidx={idx}/>
                                            </div>
                                        case 'LongTextAnswer':
                                            return <div key={ele.id + '_' + i + 'key'}>
                                                <label>{ele.name}</label>
                                                <textarea id={ele.id + '_' + i} style={{ border: `1px solid ${convertToRgbaCSS(designCon.answerBorderColor)}` }} required={ele.required} placeholder={ele.placeholder} value={ele.value} onChange={handleChange} inputidx={i} listidx={idx}></textarea>
                                            </div>
                                    }
                                })}
                                <button type="button" style={{
                                        background: convertToRgbaCSS(
                                            designCon.buttonColor
                                        ),
                                        color: convertToRgbaCSS(
                                            designCon.buttonTextColor
                                        ),
                                    }} onClick={() => {changeCurrentTab(1);}}>{item.buttonLabel}</button>
                            </div>
                        </div>
                    </div>
                )
            default:
                return applyFilters( 'renderContentElementsDesignPreview', '', item, style, convertToRgbaCSS, changeCurrentTab, designCon, handleRadioChange, idx );
        }
    };

    const handleChange = (e) => {
        let inputidx = e.target.getAttribute('inputidx');
        let listidx = e.target.getAttribute('listidx');
        let newList = JSON.parse(JSON.stringify(componentList));
        newList[listidx].List[inputidx].value = e.target.value;
        setComponentList(newList);
    }

    const handleCheckboxChange = (e) => {
        let inputidx = e.target.getAttribute('inputidx');
        let listidx = e.target.getAttribute('listidx');
        let newList = JSON.parse(JSON.stringify(componentList));
        newList[listidx].answers[inputidx].checked = !newList[listidx].answers[inputidx].checked;
        setComponentList(newList);
    }

    const handleRadioChange = (e) => {
        let listidx = e.target.getAttribute('listidx');
        let newList = JSON.parse(JSON.stringify(componentList));
        newList[listidx].value = e.target.value;
        setComponentList(newList);
    }

    designBackground( designCon );

    const checkButtonDisability = ( buttonType ) => {
        switch( buttonType ) {
            case 'Previous':
                return currentTab === 0;

            case 'Next':
                return currentTab === tabCount - 1 || componentList[currentTab].componentName === 'FormElements' || ! checkValidations( 1, true ) || componentList[currentTab].type === 'RESULT_ELEMENTS';
        }
    }

    const checkButtonVisibility = ( buttonType ) => {
        switch( buttonType ) {
            case 'Previous':
                return (currentTab !== 0 && (componentList[currentTab].type !== 'RESULT_ELEMENTS' || componentList[currentTab].type !== 'START_ELEMENTS'))
            case 'Next':
                return (currentTab !== tabCount - 1 && (componentList[currentTab].type !== 'RESULT_ELEMENTS' || componentList[currentTab].type !== 'START_ELEMENTS'));
        }
    }
    
    return (
        <div className="surveyfunnel-lite-survey-form" style={{fontFamily: designCon.fontFamily, ...designCon.backgroundStyle, height: 'calc(100vh - 108px)'}}>
            {tabCount === 0 ? (
                <div className="no-preview-available">
                    <img src={require(`../Build/BuildImages/unavailable.png`)}></img>
                    {currentlyPreviewing
                        ? "No Preview Available"
                        : "No Questions were added in this survey"}
                </div>
            ) : (
                <div className="surveyfunnel-lite-design-preview-container" style={{  }}>
                    <div className="preview" style={{color: convertToRgbaCSS( designCon.fontColor ) }}>
                        <div className="preview-container">

                        <div className="tab-list" style={{background: convertToRgbaCSS( designCon.backgroundContainerColor )}}>
                            {componentList.map(function (item, i) {
                                if (currentTab === i) {
                                    switch(item.componentName){
                                        case 'CoverPage':
                                        case 'ResultScreen':
                                            return renderContentElements(item, "flex", i);
                                        case 'SingleChoice':
                                        case 'MultiChoice':
                                        case 'FormElements':
                                        case 'ShortAnswer':
                                        case 'LongAnswer':
                                            return renderContentElements(item, "block", i);
										default:
											return applyFilters( 'callRenderContentElements', '', renderContentElements, item, i );
                                    }
                                }
                                return renderContentElements(item, 'none', i);
                            })}
                        </div>
                        {error.length > 0 && <div className="tab-validation-error">
                            {error.map(function(err) {
                                return err;
                            })}	
                        </div>}
                    
                        
                        </div>

                    </div>
                    <div className="tab-controls">
                            <span className="tab-controls-inner">
                            <div><a target="_blank" href="https://www.surveyfunnel.com"><span style={{fontSize: '10px'}}>Powered By</span><img src={require('../../../images/surveyfunnel-lite-main-logo.png')} alt="surveyfunnel-lite-main-logo" /></a></div>
                            
                            <button
                                type="button"
                                onClick={() => {
                                    changeCurrentTab(-1, 'prev');
                                }}
                                disabled={checkButtonDisability('Previous')}
                            >
                                &lt;
                            </button>
                            
                            <button
                                type="button"
                                onClick={() => {
                                    changeCurrentTab(1);
                                }}
                                disabled={checkButtonDisability('Next')}
                            >
                                &gt;
                            </button>
                            { componentList[currentTab].type === 'RESULT_ELEMENTS'  && <div><button onClick={() => {
                                                setCurrentTab(0);
                                            }}>
                                                Complete Survey    
							</button></div>}
                            </span>
                        </div>
                </div>
            )}
        </div>
    );
}
