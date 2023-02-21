import React, { Component } from 'react';
import Ad from '../Components/Ad';

class SingleAd extends Component {

	static slug = 'adsanity_divi_single_ad';

	render() {
		return <div>
			<Ad data={ this.props }/>
			<div style={ {
				clear   : 'both',
				display : 'table',
			} }></div>
		</div>;
	}

}

export default SingleAd;
