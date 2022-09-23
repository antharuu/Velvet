import { getIdent, removeIndent } from "./Tools";
import { BlockTree, VBlock } from "./Types/BlockTree";
import VelvetConfig from "./VelvetConfig";

export default class BTConverter {
	/**
	 * Convert Velvet code to Block tree
	 *
	 * @param velvetCode input Velvet code
	 * @returns Block tree
	 */
	static convert(velvetCode: string): BlockTree {
		this.getSubBlockOf(velvetCode);

		return [];
	}

	/**
	 * Return the sub block of a block
	 *
	 * @return sub block
	 */
	static getSubBlockOf(block: string): BlockTree {
		const lines = block.split("\n").filter((l) => l.trim().length > 0),
			tabSize = VelvetConfig.get().tabSize;

		if (lines.length > 0) {
			// --------------- If block not empty

			let firstBlockLine = lines.shift() ?? "",
				blocks: BlockTree = [],
				currentBlock: VBlock | null,
				currentSubLines: string[] = [];

			const curentIndent = getIdent(firstBlockLine, tabSize);

			function endBlock() {
				blocks.push({
					line: firstBlockLine,
					block: BTConverter.getSubBlockOf(
						currentSubLines.join("\n")
					),
				});

				currentSubLines = [];
				currentBlock = null;
				firstBlockLine = lines.shift() ?? "";
			}

			lines.forEach((line: string) => {
				if (getIdent(line, tabSize) > curentIndent) {
					currentSubLines.push(removeIndent(line, tabSize));
				} else {
					endBlock();
				}
			});

			endBlock();

			return blocks ?? [];
		}

		return [];
	}
}
