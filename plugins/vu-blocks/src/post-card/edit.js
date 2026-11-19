import { useBlockProps } from '@wordpress/block-editor';
import { ServerSideRender } from '@wordpress/server-side-render';
import block from './block.json';

export default function (props) {
	const { context } = props;
	const blockProps = useBlockProps();

	return (
		<div {...blockProps}>
			<ServerSideRender
				block={block.name}
				attributes={{
					postId: context?.postId,
				}}
			/>
		</div>
	);
}
