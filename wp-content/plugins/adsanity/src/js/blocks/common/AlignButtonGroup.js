import AlignButton from './AlignButton';

//
// WordPress scripts
//
const { __, }          = window.wp.i18n;
const { Component, }   = window.wp.element;
const { ButtonGroup, } = window.wp.components;

class AlignButtonGroup extends Component {

	constructor() {
		super( ...arguments );
		this.onAlignChange = this.onAlignChange.bind( this );
	}

	onAlignChange( value ) {
		this.props.onAlignChange( value );
	}

	render() {

		const { propValue, } = this.props;

		return (
			<ButtonGroup
				className="adsanity-alignment"
			>
				<p>{ __( 'Align:', 'adsanity' ) }</p>
				<div className="buttons">
					<AlignButton
						propValue={ propValue }
						align="alignleft"
						icon="editor-alignleft"
						label={ __( 'left', 'adsanity' ) }
						onClick={ this.onAlignChange }
					/>
					<AlignButton
						propValue={ propValue }
						align="aligncenter"
						icon="editor-aligncenter"
						label={ __( 'center', 'adsanity' ) }
						onClick={ this.onAlignChange }
					/>
					<AlignButton
						propValue={ propValue }
						align="alignright"
						icon="editor-alignright"
						label={ __( 'right', 'adsanity' ) }
						onClick={ this.onAlignChange }
					/>
					<AlignButton
						propValue={ propValue }
						align="alignnone"
						icon="no-alt"
						label={ __( 'none', 'adsanity' ) }
						onClick={ this.onAlignChange }
					/>
				</div>
			</ButtonGroup>
		);

	}

}

export default AlignButtonGroup;
