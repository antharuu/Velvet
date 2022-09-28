"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const Config_1 = require("./Types/Config");
class VelvetConfig {
    static init(config = {}) {
        VelvetConfig.__initialized = true;
        VelvetConfig.config = Object.assign(Object.assign({}, Config_1.DefaultConfig), config);
    }
    static get() {
        if (!VelvetConfig.__initialized)
            VelvetConfig.init();
        return VelvetConfig.config;
    }
}
exports.default = VelvetConfig;
VelvetConfig.__initialized = false;
//# sourceMappingURL=VelvetConfig.js.map