//
// React Select uses Emotion CSS.
// We need a custom cache provider in case
// this block is loaded in an iframe (Full Site Editor)
//
import createCache from '@emotion/cache';
import { CacheProvider } from '@emotion/react';
import { useRef, useState, useEffect, cloneElement, Children } from 'react';

//
// Wrap (single) child in it's own Emotion.js cache context.
//
function EmotionCache( { children } ) {

	const [ container, setContainer ] = useState( null );

	useEffect( () => {
		setContainer( ref.current );
	}, [] );

	// Generate a unique key for emotion.
	let key = '';
	const alpha = 'abcdefghijklmnopqrstuvwxyz';
	for ( var i = 0; i < 10; i++ ) {
		key += alpha.charAt( Math.floor( Math.random() * alpha.length ) );
	}

	const ref = useRef( null );
	const cache = createCache( {
		key      : key,
		container: container,
	} );

	return (
		<div ref={ ref }>
			<CacheProvider value={ cache }>
				{
					cloneElement(
						Children.only( children ),
						{
							emotion: key,
						}
					)
				}
			</CacheProvider>
		</div>
	);
}

export default EmotionCache;
