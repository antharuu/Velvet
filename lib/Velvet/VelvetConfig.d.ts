import { Config, FullConfig } from "./Types/Config";
export default class VelvetConfig {
    private static __initialized;
    private static config;
    static init(config?: Config): void;
    static get(): FullConfig;
}
