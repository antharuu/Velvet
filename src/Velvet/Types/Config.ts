export type FullConfig = {
	tabSize: TabSize;
	prettyHTML: false;
};

export type Config = Partial<FullConfig>;

export const DefaultConfig: FullConfig = {
	tabSize: "tab",
	prettyHTML: false,
};

export type TabSize = "tab" | 2 | 4;
