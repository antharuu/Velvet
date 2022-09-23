export type VNode = VText | VTag;

export type AST = Array<VNode>;

export type VText = string;

export type VTag = {
	tag: string;
	childs?: AST;
	attributes?: VAttributes[];
	indent: number;
};

export type VAttributes = {
	name: string;
	value?: string | string[] | null;
};
