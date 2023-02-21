import React from "react"

function SvgComponent(props) {
	return (
		<svg width={20} height={20} viewBox="0 0 20 20" {...props}>
		<path
			d="M18 0h-7C9.9 0 9 .9 9 2v7.7l1.3 1.3H18c1.1 0 2-.9 2-2V2c0-1.1-.9-2-2-2z"
			fill="#c3bddc"
		/>
		<path
			d="M10 10l-4.3-.2L6 5c-1.1 0-2 .9-2 2v7c0 1.1.9 2 2 2h7c1.1 0 2-.9 2-2l-4.7.5L10 10z"
			fill="#d6bef4"
		/>
		<path
			d="M14 4H7c-1.1 0-2 .9-2 2v3.5h1.2V6c0-.4.4-.8.8-.8h7c.4 0 .8.4.8.8v7c0 .4-.4.8-.8.8h-3.5V15H14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2z"
			fill="#37238c"
		/>
		<path
			d="M9 10.5c.3 0 .5.2.5.5v7c0 .3-.2.5-.5.5H2c-.3 0-.5-.2-.5-.5v-7c0-.3.2-.5.5-.5h7M9 9H2c-1.1 0-2 .9-2 2v7c0 1.1.9 2 2 2h7c1.1 0 2-.9 2-2v-7c0-1.1-.9-2-2-2z"
			fill="#7826dc"
		/>
		</svg>
	)
}

export default SvgComponent
