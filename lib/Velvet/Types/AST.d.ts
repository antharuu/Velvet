export declare type VNode = VText | VTag;
export declare type AST = Array<VNode>;
export declare type VText = string;
export declare type VTag = {
    tag: string;
    children: AST;
    attributes?: VAttributes;
    indent: number;
};
export declare type VAttributes = {
    [key: string]: string | string[] | number | number[] | null;
};
export declare type TempBlock = {
    line: string;
    block: TempBlock[];
};
//# sourceMappingURL=AST.d.ts.map