import React, { useEffect, useState, useContext } from 'react'
import fetchData from '../../HelperComponents/fetchData';
const { applyFilters, doAction } = wp.hooks;
import Select from 'react-select';
import { ConfigureContext } from '../Context/ConfigureContext';

export default function GeneralSettings() {
	
	// get the required ConfigureContext data and functions.
	const { metaInfo, companyBranding, setCompanyBranding, options, setOptions, handleMetaChange, saveConfiguration, proSettings, setProSettings } = useContext(ConfigureContext);

	return (
			<div className="configurationRight">
				<div className="meta-info">
					<div className="configure-fields">
						<div className="meta-info-container">
							<h3>Meta Information</h3>
							<p>Used by social networks and search engines</p>
							<label htmlFor="title">Title: </label>
							<input type="text" value={metaInfo.title} name="title" onChange={handleMetaChange} />
							<label htmlFor="description">Description: </label>
							<textarea value={metaInfo.description} name="description" cols="30" rows="10" onChange={handleMetaChange}></textarea>

						</div>
						<div className="company-name-container">
							<h3>Company Branding</h3>
							<div className="companyLogoBox">
								<p>Logo visible on deployed content:</p>
								<input id="useCompanyLogo" type="checkbox" checked={companyBranding} onChange={() => {setCompanyBranding(!companyBranding)}} />
								<label htmlFor="useCompanyLogo" > </label>
							</div>
						</div>
						{applyFilters('renderPrivacyPolicySettings', '', setProSettings, proSettings, fetchData, Select, options, setOptions )}
					</div>

					<div className="meta-info-save-container">
						<button type="button" onClick={saveConfiguration}>Save</button>
					</div>
				</div>
			</div>
	)
}
