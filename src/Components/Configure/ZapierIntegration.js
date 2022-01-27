import React, { useEffect, useState, useContext } from 'react'
import fetchData from '../../HelperComponents/fetchData';
import '../../scss/configure.scss';
const { applyFilters, doAction } = wp.hooks;
import Select from 'react-select';
import { ConfigureContext } from '../Context/ConfigureContext';
import {shortcodeTypes} from "../../Data";

export default function Configure() {
	
	// get the required ConfigureContext data and functions.
	const { metaInfo, companyBranding, setCompanyBranding, options, setOptions, handleMetaChange, saveConfiguration, proSettings, setProSettings } = useContext(ConfigureContext);
	let apiKeyInputRef = React.createRef();
	const [ copyStatus, setCopyStatus ] = useState('Copy API Key');
	const [apiKey, setApiKey] = useState('');

	useEffect(() => {
		let post_id = new URLSearchParams(window.location.search).get('post_id');
		const ajaxSecurity = document.getElementById('ajaxSecurity').value;
		const data = {
			security: ajaxSecurity,
			action: 'surveyfunnel_lite_get_api_key',
			post_id
		};

		const ajaxURL = document.getElementById('ajaxURL').value;
		fetchData( ajaxURL, data )
			.then(data => {
				if ( data.data.apikey !== null ) {
					setApiKey(data.data.apikey);
				}
				else {
					setApiKey('API Key has not been activated. Go to Settings -> SurveyFunnel Pro Activation');
				}
			})

	}, [])

	const copyApiKey = () => {
		apiKeyInputRef.current.removeAttribute('disabled');
		apiKeyInputRef.current.select()
		apiKeyInputRef.current.setSelectionRange(0, 99999)
		document.execCommand("copy");
		apiKeyInputRef.current.setAttribute('disabled', true);
		setCopyStatus('Copied!');
		setTimeout(() => {
			setCopyStatus('Copy API Key');
		}, 4000)
	}

	return (
			<div className="configureZapierSettings">
				<div className="contentApiKeyLabel-container">
						<h3>Zap Integrations</h3>
						<p>Select from our commonly used Zaps and use our API key to connect SurveyFunnel with your favorite tool.</p>
							<div className="contentApiKeyLabel">
								<h3>API Key: </h3>
								<button onClick={copyApiKey}>{copyStatus}</button>
							</div>
							<input
							type="text"
							id="contentApiKey"
							disabled
							ref={apiKeyInputRef}
							onChange={() => {}}
							value={apiKey}
						/>
						</div>
						<div className="zapierSteps">
								<p>Connect to Zapier using following guidelines.</p>
								<ol>
									<li>Create a Survey.</li>
									<li><a href="https://zapier.com/app/connections" target="_blank">Create a Zap</a> on zapier.com.</li>
									<li><a href="https://docs.wpeka.com/survey-funnel/v/master/zapier-integration"_target="_blank">Click here for documentation on connecting SurveyFunnel with Zapier.</a></li>
								</ol>
						</div>

			</div>
	)
}
