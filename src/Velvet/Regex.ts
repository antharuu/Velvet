export const LineRegex: RegExp = /^(?<tag>\w+ ?)(?<rest>.*)/;

export function RegexParse(
  str: string,
  regex: RegExp
): { [key: string]: string } | null {
  let m: RegExpExecArray | null;
  if ((m = regex.exec(str)) !== null) {
    if (m.groups) {
      return m.groups;
    }
  }
  return null;
}
