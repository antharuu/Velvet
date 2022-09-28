import { Config, DefaultConfig, FullConfig } from "./Types/Config.js";

export default class VelvetConfig {
	private static __initialized = false;

	private static config: FullConfig;

	public static init(config: Config = {}) {
		VelvetConfig.__initialized = true;
		VelvetConfig.config = {
			...DefaultConfig,
			...config,
		};
	}

	public static get(): FullConfig {
		if (!VelvetConfig.__initialized) VelvetConfig.init();
		return VelvetConfig.config;
	}
}
