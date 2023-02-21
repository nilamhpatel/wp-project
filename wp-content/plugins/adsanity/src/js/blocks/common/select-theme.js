import colors from './colors';

export default ( theme ) => ({
	...theme,
	borderRadius: 0,
	colors: {
	...theme.colors,
		primary25: colors.primaryLight,
		primary: colors.primary,
	},
} );
