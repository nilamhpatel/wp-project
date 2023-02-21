//
// WordPress scripts
//
const { Component, }         = window.wp.element;
const { Button, IconButton } = window.wp.components;

class AlignButton extends Component {

	constructor() {
		super(...arguments);
		this.onClick = this.onClick.bind( this );
	}

	onClick( value ) {
		this.props.onClick( value );
	}

	render() {

		const { propValue, align, icon, label, } = this.props;

		return (
				<IconButton
					isDefault
					className={ propValue === align ? 'active-button' : '' }
					isToggled={ propValue === align }
					icon={ icon }
					label={ label }
					onClick={ () => {
						this.onClick( align );
					} }
				/>
		);

	}

}

export default AlignButton;
