export type VNode = VText | VTag;

export type AST = Array<VNode>;

export type VText = string;

export type VTag = {
	tag: string;
	children: AST;
	attributes?: VAttributes[];
	indent: number;
};

export type VAttributes = {
	name: string;
	value?: string | string[] | null;
};

export type TempBlock = {
	line: string;
	block: TempBlock[];
};
