//
// WordPress scripts
//
const { __, }        = window.wp.i18n;
const { Component, } = window.wp.element;

class AutomaticInclusion extends Component {

	constructor() {
		super(...arguments);
	}

	render() {

		if ( ! ADSANITY_AUTOMATIC_ADS.automatic_ads ) {
			return '';
		}

		return (
			<div
				className="automatic-ads"
			>
				<hr className="adsanity-section-separator"/>

				<p><strong>{ __( 'This post includes automatically placed ads', 'adsanity' ) }</strong></p>

				<p className="link"><a href={ ADSANITY_AUTOMATIC_ADS.settings_url }>{ __( 'Edit Settings Here', 'adsanity' ) }</a></p>
			</div>
		);

	}

}

export default AutomaticInclusion;
