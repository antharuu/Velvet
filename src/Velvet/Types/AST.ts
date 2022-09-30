export type VNode = VText | VTag;

export type AST = Array<VNode>;

export type VText = string;

export type VTag = {
	tag: string;
	children: AST;
	attributes?: {
		[key: string]: string | string[] | number | number[] | null
	};
	indent: number;
};

export type TempBlock = {
	line: string;
	block: TempBlock[];
};
