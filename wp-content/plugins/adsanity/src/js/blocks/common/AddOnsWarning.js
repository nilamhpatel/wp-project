//
// WordPress scripts
//
const { __, }        = window.wp.i18n;
const { Component, } = window.wp.element;

class AddOnsWarning extends Component {

	constructor() {
		super( ...arguments );
	}

	render() {

		const conditionalAds = ADSANITY_ADD_ONS_INFO.conditional;
		const userRole       = ADSANITY_ADD_ONS_INFO.user_role;

		let message = '';

		if ( conditionalAds && userRole ) {
			message = __( 'Conditional Ad Appearance and User Role Ad Visibility Add-Ons are active. This may affect ad visibility on this post.', 'adsanity' );
		} else if ( conditionalAds ) {
			message = __( 'Conditional Ad Appearance Add-On is active. This may affect ad visibility on this post.', 'adsanity' );
		} else if ( userRole ) {
			message = __( 'User Role Ad Visibility Add-On is active. This may affect ad visibility on this post.', 'adsanity' );
		} else {
			return '';
		}

		return (
			<div class="adsanity-add-ons-warning">
				<p><strong>{ message }</strong></p>
			</div>
		);
	}

}

export default AddOnsWarning;
