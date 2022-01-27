import { createContext, Component, useState, useContext, useEffect } from "react";
const { applyFilters, doAction } = wp.hooks;
import fetchData from "../../HelperComponents/fetchData";

export function ConfigureContextProvider( props ) {

	// metaInfo state.
	const [metaInfo, setMetaInfo] = useState({
		title: '',
		description: ''
	});

	// companybranding state.
	const [companyBranding, setCompanyBranding] = useState(true);
	const [proActive, setProActive] = useState(true);
	const [proSettings, setProSettings] = useState({
		...applyFilters( 'configureProState', {} )
	});

	// get posts and pages state.
	const [options, setOptions] = useState([]);

	// useEffect hook to get configuration data when component is mounted.
	useEffect(() => {
		const ajaxSecurity = document.getElementById('ajaxSecurity').value;
        const post_id = new URLSearchParams(window.location.search).get('post_id');
        const data = {
            security: ajaxSecurity,
            action: 'surveyfunnel_lite_get_configuration_data',
            post_id,
        };
        const ajaxURL = document.getElementById('ajaxURL').value;
        fetchData( ajaxURL, data )
        .then(data => {
            if ( data.data.configure === '' ) {
				return;
			}
            setProActive(data.data.proActive);
			let configure = JSON.parse(data.data.configure);
			setMetaInfo(configure.metaInfo);
			setCompanyBranding(configure.companyBranding);
			doAction( 'configureMount', configure, setProSettings );
        })

		const getOptions = () => {
			const ajaxSecurity = document.getElementById('ajaxSecurity').value;
			const post_id = new URLSearchParams(window.location.search).get('post_id');
			const data = {
				security: ajaxSecurity,
				action: 'surveyfunnel_lite_get_posts_pages',
				post_id,
				links: true,
			};
			const ajaxURL = document.getElementById('ajaxURL').value;
			fetchData( ajaxURL, data )
			.then(data => {
				setOptions(data.data);
			})
		}
	
		getOptions();
	}, []);

	// handle change in meta.
	const handleMetaChange = (e) => {
		setMetaInfo({
			...metaInfo,
			[e.target.name]: e.target.value,
		});
	}

	// save configuration data.
	const saveConfiguration = (e) => {
		e.target.classList.add('surveyfunnel-lite-button-loading');
		const ajaxSecurity = document.getElementById('ajaxSecurity').value;
        const post_id = new URLSearchParams(window.location.search).get('post_id');
        const data = {
            security: ajaxSecurity,
            action: 'surveyfunnel_lite_save_configuration_data',
            post_id,
			configuration: JSON.stringify({metaInfo, companyBranding, proSettings})
        };
        const ajaxURL = document.getElementById('ajaxURL').value;
        fetchData( ajaxURL, data )
        .then(data => {
			e.target.classList.remove('surveyfunnel-lite-button-loading');
        })
	}

	const value = {
		metaInfo, setMetaInfo, companyBranding, setCompanyBranding, options, setOptions, handleMetaChange, saveConfiguration, proActive, proSettings, setProSettings
	}
	return(
		<ConfigureContext.Provider
			value={value}
		>
			{props.children}
		</ConfigureContext.Provider>
	);
}

export const ConfigureContext = createContext();