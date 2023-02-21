import React from "react"

function SvgComponent(props) {
	return (
		<svg width={20} height={20} viewBox="0 0 20 20" {...props}>
		<path
			d="M16 14H8c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h8c1.1 0 2 .9 2 2v8c0 1.1-.9 2-2 2z"
			fill="#d6bef4"
		/>
		<path
			d="M13 6.5c.3 0 .5.2.5.5v9c0 .3-.2.5-.5.5H4c-.3 0-.5-.2-.5-.5V7c0-.3.2-.5.5-.5h9M13 5H4c-1.1 0-2 .9-2 2v9c0 1.1.9 2 2 2h9c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2z"
			fill="#7826dc"
		/>
		</svg>
	)
}

export default SvgComponent
