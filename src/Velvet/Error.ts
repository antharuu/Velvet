export function error(category: string, cause: string) {
	throw new Error(`Velvet (${category})\n ${cause}`);
}
