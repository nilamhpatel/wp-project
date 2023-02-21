//
// WordPress scripts
//
const { __, }        = window.wp.i18n;
const { Component, } = window.wp.element;
const {
	CheckboxControl,
	Tooltip,
	IconButton,
} = window.wp.components;

import colors from './colors';
import questionIcon from './questionIcon.js';

class MaxWidthControl extends Component {

	constructor() {
		super( ...arguments );
		this.onMaxWidthEnabledChange = this.onMaxWidthEnabledChange.bind( this );
		this.onMaxWidthChange = this.onMaxWidthChange.bind( this );
	}

	onMaxWidthEnabledChange( value ) {
		this.props.onMaxWidthEnabledChange( value );
	}

	onMaxWidthChange( event ) {
		let value = event.target.value.replace( /\D/g, '' );
		if ( value < 1 ) {
			value = '';
		}
		this.props.onMaxWidthChange( value );
	}

	render() {

		const { maxWidthEnabled, maxWidth, } = this.props;

		return (
			<div className="adsanity-max-width-control">
				<div
					style={ {
						display: 'flex',
						justifyContent: 'center',
						marginTop: '10px',
					} }
				>
					<CheckboxControl
						label={ __( 'Max Width Enabled?', 'adsanity' ) }
						onChange={ this.onMaxWidthEnabledChange }
						checked={ maxWidthEnabled }
						style={ {
							backgroundColor: maxWidthEnabled ? colors.primary : colors.white,
							borderColor: maxWidthEnabled ? colors.primary : '',
						} }
					/>
					<Tooltip
						text={ __( 'Set a maximum width for this ad. This is helpful for responsive ads.', 'adsanity' ) }
					>
						<IconButton
							isDefault
							className="adsanity-tooltip-button"
							icon={ questionIcon }
							label={ __( 'Hover for more information', 'adsanity' ) }
						/>
					</Tooltip>
				</div>
				{ maxWidthEnabled && (
					<label>
						<span>{ __( 'Max width:', 'adsanity' ) }</span>
						<input
							type="text"
							style={ { marginLeft: '12px', } }
							onChange={ this.onMaxWidthChange }
							value={ maxWidth }
						/>
					</label>
				) }
			</div>
		);

	}

}

export default MaxWidthControl;
