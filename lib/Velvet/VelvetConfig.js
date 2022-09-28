"use strict";
var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
Object.defineProperty(exports, "__esModule", { value: true });
var Config_1 = require("./Types/Config");
var VelvetConfig = /** @class */ (function () {
    function VelvetConfig() {
    }
    VelvetConfig.init = function (config) {
        if (config === void 0) { config = {}; }
        VelvetConfig.__initialized = true;
        VelvetConfig.config = __assign(__assign({}, Config_1.DefaultConfig), config);
    };
    VelvetConfig.get = function () {
        if (!VelvetConfig.__initialized)
            VelvetConfig.init();
        return VelvetConfig.config;
    };
    VelvetConfig.__initialized = false;
    return VelvetConfig;
}());
exports.default = VelvetConfig;
