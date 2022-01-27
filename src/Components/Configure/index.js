import React, { useEffect, useState, useContext } from 'react'
import fetchData from '../../HelperComponents/fetchData';
const { applyFilters, doAction } = wp.hooks;
import { configureTabsData } from "../../Data";
import Select from 'react-select';
import { ConfigureContext } from '../Context/ConfigureContext';
import Tabs from "../../HelperComponents/Tabs";
import GeneralSettings from "./GeneralSettings";
import ZapierIntegration from "./ZapierIntegration";
import '../../scss/configure1.scss';

// function decides which component to render based on currentConfigureTab.
const getCurrentTabContent = ( currentConfigureTab ) => {
    const { proActive } = useContext(ConfigureContext);
	switch( currentConfigureTab ) {
		case 'generalsettings':
			return <GeneralSettings />
		case 'zapier':
			if(proActive){
                return <ZapierIntegration />
			}
			else {
				return <GeneralSettings />
			}
		default:
			return '';
	}
}

export default function Configure() {

    const { proActive } = useContext(ConfigureContext);

	// state to keep in track of currentTab defualt is generalsettings.
	const [ currentConfigureTab, setCurrentConfigureTab ] = useState('generalsettings');

	const changeCurrentConfigureTab = ( id ) => {
		setCurrentConfigureTab(id);
	}

	return (
		<div className="Configure">
		<div className="configureTabs">
		{configureTabsData.map(function(item, i) {
                return <div key={i} className={(!proActive && ( item.id === 'zapier')) ? 'configureTabs-element-disabled' : 'configureTabs-element'} style={{cursor: 'pointer'}} onClick={()=>{changeCurrentConfigureTab(item.id)}}>
			<div className="configureTabs-element-title">
					<h3>{item.name}</h3>
					<p>{item.description}</p>
					</div>
				{item.id === currentConfigureTab && <img src={require('../Build/BuildImages/arrowRight.png')}></img> }
				</div>
				})}
		</div>
		<div className="configureTabContent">
		<div className="configureTabContentContainer">
		{getCurrentTabContent( currentConfigureTab )}
		</div>
		</div>
		</div>
);
}
