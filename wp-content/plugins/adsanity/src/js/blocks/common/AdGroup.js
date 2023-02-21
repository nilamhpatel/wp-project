import SingleAd from './SingleAd';

//
// WordPress scripts
//
const { __, }        = window.wp.i18n;
const { Component, } = window.wp.element;

class AdGroup extends Component {

	constructor() {
		super( ...arguments );
		this.onRenderedAd = this.onRenderedAd.bind( this );
	}

	onRenderedAd( value ) {
		this.props.onRenderedAd( value );
	}

	render() {

		const { adIds, align, numAds, numColumns, maxWidthEnabled, maxWidth, noAdMessage, } = this.props;

		if ( ! adIds.length ) {
			return <h2>{ noAdMessage }</h2>
		}

		adIds.splice( numAds, adIds.length - numAds );
		const rows = [];
		const numOfRows = Math.ceil( numAds / numColumns );

		for ( let i = 0; i < numOfRows; i++ ) {
			const adsInRow = [];
			const startingIndex = i * numColumns;
			const lastIndex = startingIndex + Number.parseInt( numColumns );

			for (
				let j = startingIndex;
				j < lastIndex && j < adIds.length;
				j++
			) {

				adsInRow.push(
					<SingleAd
						adId={ adIds[ j ].id }
						renderedAd={ adIds[ j ] }
						align="alignnone"
						maxWidthEnabled={ maxWidthEnabled }
						maxWidth={ maxWidth }
						onRenderedAd={ this.onRenderedAd }
					/>
				);
			}

			rows.push(
				<div className="ad-row">
					{ adsInRow }
				</div>
			);
		}

		return (
			<div
				className={ `ad-${ align }` }
			>
				{ rows }
			</div>
		);

	}

}

export default AdGroup;
