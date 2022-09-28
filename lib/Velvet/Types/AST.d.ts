export declare type VNode = VText | VTag;
export declare type AST = Array<VNode>;
export declare type VText = string;
export declare type VTag = {
    tag: string;
    children: AST;
    attributes?: VAttributes[];
    indent: number;
};
export declare type VAttributes = {
    name: string;
    value?: string | string[] | null;
};
export declare type TempBlock = {
    line: string;
    block: TempBlock[];
};
//# sourceMappingURL=AST.d.ts.map