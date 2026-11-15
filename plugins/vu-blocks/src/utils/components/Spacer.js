/**
 * A simple spacer.
 * @param {Object} props        The component props.
 * @param {number} props.height Height value
 * @return {Element}            The Spacer component.
 */
export default function Spacer({ height = 10 }) {
	return <div style={{ height: `${height}px` }} />;
}
