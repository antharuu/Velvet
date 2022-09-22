type FullConfig = {
	tabSize: "tab" | 2 | 4;
	prettyHTML: false;
};

export type Config = Partial<FullConfig>;

export const DefaultConfig: FullConfig = {
	tabSize: "tab",
	prettyHTML: false,
};
