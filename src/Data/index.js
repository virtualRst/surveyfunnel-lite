const { applyFilters } = wp.hooks;

const ItemTypes = {
	CARD: 'card',
	START_ELEMENTS: 'START_ELEMENTS',
	CONTENT_ELEMENTS: 'CONTENT_ELEMENTS',
	RESULT_ELEMENTS: 'RESULT_ELEMENTS'
}  

let buildElements = {
	startScreen: [
		{
			name: 'Cover Page',
			componentName: 'CoverPage',
			itemType: ItemTypes.START_ELEMENTS,
		}
	],
	contentElements: [
		{
			name: 'Single Choice',
			componentName: 'SingleChoice',
			itemType: ItemTypes.CONTENT_ELEMENTS
		},
		{
			name: 'Multi Choice',
			componentName: 'MultiChoice',
			itemType: ItemTypes.CONTENT_ELEMENTS
		},
		{
			name: 'Form Elements',
			componentName: 'FormElements',
			itemType: ItemTypes.CONTENT_ELEMENTS
		},
		{
			name: 'Short Answer',
			componentName: 'ShortAnswer',
			itemType: ItemTypes.CONTENT_ELEMENTS,
			value: ''
		},
		{
			name: 'Long Answer',
			componentName: 'LongAnswer',
			itemType: ItemTypes.CONTENT_ELEMENTS,
			value: ''
		},
		...applyFilters('contentElements', [])
	],
	resultScreen: [
		{
			name: 'Results Page',
			componentName: 'ResultScreen',
			itemType: ItemTypes.RESULT_ELEMENTS
		}
	]
};

const dropBoard = [
	{
		name: 'Start Screen',
		type: 'StartScreen',
		itemType: ItemTypes.START_ELEMENTS
	},
	{
		name: 'Content Elements',
		type: 'ContentElements',
		itemType: ItemTypes.CONTENT_ELEMENTS
	},
	{
		name: 'Results Screen',
		type: 'ResultsScreen',
		itemType: ItemTypes.RESULT_ELEMENTS
	}
];

const formElementsDropBoard = [
	{
		name: 'Form Fields',
		type: 'FormFields',
		itemType: 'FORMFIELDS_ELEMENTS'
	}
]

let formElements = [
	{
		name: 'First Name',
		componentName: 'FirstName',
		itemType: 'FORMFIELDS_ELEMENTS',
		required: false,
		placeholder: '',
		value: '',
	},
	{
		name: 'Last Name',
		componentName: 'LastName',
		itemType: 'FORMFIELDS_ELEMENTS',
		required: false,
		placeholder: '',
		value: '',
	},
	{
		name: 'Email',
		componentName: 'Email',
		itemType: 'FORMFIELDS_ELEMENTS',
		required: false,
		placeholder: '',
		value: ''
	},
    {
        name: 'Phone',
        componentName: 'Phone',
        itemType: 'FORMFIELDS_ELEMENTS',
        required: false,
        placeholder: '',
        value: ''
    },
    {
        name: 'Organisation',
        componentName: 'Organisation',
        itemType: 'FORMFIELDS_ELEMENTS',
        required: false,
        placeholder: '',
        value: ''
    },
]
const designColors = [
	{
		name: 'Font Color',
		itemName: 'fontColor'
	},
	{
		name: 'Background Color',
		itemName: 'backgroundColor'
	},
	{
		name: 'Button Color',
		itemName: 'buttonColor'
	},
	{
		name: 'Button Text Color',
		itemName: 'buttonTextColor'
	},
	{
		name: 'Answers Highlight Box Color',
		itemName: 'answersHighlightBoxColor'
	},
	{
		name: 'Answer Border Color',
		itemName: 'answerBorderColor'
	},
	{
		name: 'Background Container Color',
		itemName: 'backgroundContainerColor'
	},
]

const initColorState = {
	opacity: 0,

	fontFamily: null,
	fontFamilyValue: '',
	
	backgroundColor: {
		r: '255',
		g: '255',
		b: '255',
		a: '1'
	},

	buttonColor: {
		r: '1',
		g: '111',
		b: '222',
		a: '1'
	},

	buttonTextColor: {
		r: '255',
		g: '255',
		b: '255',
		a: '1'
	},

	answersHighlightBoxColor: {
		r: '232',
		g: '238',
		b: '244',
		a: '1'
	},

	answerBorderColor: {
		r: '180',
		g: '220',
		b: '255',
		a: '1'
	},

	backgroundContainerColor: {
		r: '255',
		g: '255',
		b: '255',
		a: '1'
	},

	fontColor: {
		r: '0',
		g: '0',
		b: '0',
		a: '1'
	},
}

const shortcodeTypes = [
	{
		name: 'Responsive',
		id: 'responsive',
		helpText: "This code will place a responsive survey on your website."
	},
	{
		name: 'Full Page',
		id: 'fullpage',
		helpText: "This‌ ‌code‌ ‌will‌ ‌place‌ ‌a‌ ‌full-page‌ ‌survey‌ ‌on‌ ‌your‌ ‌website.‌"
	},
	{
		name: 'Custom',
		id: 'custom',
		helpText: "This code will place a custom sized survey on your website.",
		disabled: true,
	}
]

const shareTabsData = [
	{
		name: 'Share Shortcode',
		description: ' ‌Copy‌ ‌and‌ ‌paste‌ ‌this‌ ‌into‌ ‌any‌ ‌post‌ ‌or‌ ‌page‌ ‌you‌ ‌want‌ ‌the‌ ‌survey‌ ‌to‌ ‌be‌ ‌displayed.‌',
		id: 'shortcode',
	},
	{
		name: 'Popup',
		description: '‌Enable‌ ‌a‌ ‌pop-up‌ ‌survey‌ ‌on‌ ‌selected‌ ‌pages',
		id: 'popup'
	}
]

const configureTabsData = [
	{
		name: 'General',
		description: 'Basic Settings',
		id: 'generalsettings'
	},
	{
		name: 'Zapier Integration',
		description: 'Integrate Zapier to connect hundreds of apps',
		id: 'zapier'
	}
]

const popupInitialState = {
	active: false,
	targettingOptions: {
		devices: [
			{
				name: 'Desktop',
				checked: true,
				id: 'desktop',
			},
			{
				name: 'Mobile',
				checked: true,
				id: 'mobile',
			},
			{
				name: 'Tablet',
				checked: true,
				id: 'tablet'
			}
		],
		triggerPage: 'triggerOnSpecific',
		selectedPagesAndPosts: [],
	},
	behaviourOptions: {
		launchOptions: {
			launchWhen: 'afterPageLoads',
			afterTimeDelay: 5,
			afterExitIntent: 'low',
			afterScrollPercentage: 20,
		},
		frequencyOptions: {
			frequency: 'alwaysShow',
			hideFor: 3,
			dontShowAgain: false,
		}
	}
}

const dimensionTypes = [
	'%', 'px', 'em', 'rem'
];

export { ItemTypes, buildElements, popupInitialState, shareTabsData, configureTabsData, shortcodeTypes, initColorState, designColors, formElements, formElementsDropBoard, dropBoard, dimensionTypes };

