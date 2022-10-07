export type VNode = VText | VTag;

export type AST = Array<VNode>;

export type VText = string;

export type VTag = {
	tag: string;
	children: AST;
	attributes?: VAttributes;
	indent: number;
};

export type VAttributes = {
	[key: string]: string | string[] | number | number[] | null;
};

export type BlockAttr = { current_block: TempBlock[]; attributes: VAttributes };

export type TempBlock = {
	line: string;
	block: TempBlock[];
};
