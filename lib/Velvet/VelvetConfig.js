import { DefaultConfig } from "./Types/Config.js";
export default class VelvetConfig {
    static init(config = {}) {
        VelvetConfig.__initialized = true;
        VelvetConfig.config = {
            ...DefaultConfig,
            ...config,
        };
    }
    static get() {
        if (!VelvetConfig.__initialized)
            VelvetConfig.init();
        return VelvetConfig.config;
    }
}
VelvetConfig.__initialized = false;
//# sourceMappingURL=VelvetConfig.js.map