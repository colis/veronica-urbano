export const baseIconAttributes = {
	hasLeadingIcon: {
		type: 'boolean',
		default: false,
	},
	icon: {
		type: 'string',
		default: '',
	},
	iconSize: {
		type: 'string',
		default: '',
	},
	iconGap: {
		type: 'string',
		default: '',
	},
};

export const buttonIconAttributes = {
	...baseIconAttributes,
	hasTrailingIcon: {
		type: 'boolean',
		default: false,
	},
};

export const paragraphIconAttributes = {
	...baseIconAttributes,
};
