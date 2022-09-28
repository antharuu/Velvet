export declare type FullConfig = {
    tabSize: TabSize;
    prettyHTML: false;
};
export declare type Config = Partial<FullConfig>;
export declare const DefaultConfig: FullConfig;
export declare type TabSize = "tab" | 2 | 4;
