/**
 * Get redirect data from a specified endpoint.
 *
 * @param {string} endpoint        The API endpoint to fetch data from.
 * @param {string} [authorization] Optional authorization token.
 *
 * @return {Promise<[Record<string, unknown> | null, Error | null]>} A promise that resolves to an array containing the result and error.
 */
export const getRedirectData = async (endpoint, authorization) => {
	const options = {
		method: 'GET',
		headers: {
			'Content-Type': 'application/json',
			...(authorization ? { Authorization: authorization } : {}),
		},
	};

	try {
		const response = await fetch(endpoint, options);
		if (!response.ok) {
			throw new Error(`HTTP error! status: ${response.status}`);
		}
		const result = await response.json();
		return [result, null];
	} catch (error) {
		console.warn(
			`Error in getting location redirect response. Error: ${error.message}`
		);
		return [null, error];
	}
};
