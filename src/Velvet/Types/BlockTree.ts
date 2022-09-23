export type BlockTree = VBlock[];

export type VBlock = {
	line: string;
	block: BlockTree;
};

export type BasicBlock = {
	line: string;
	block: string;
};
