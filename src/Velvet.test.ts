import Velvet from "./Velvet";

test("Parse simple tag", () => {
  expect(Velvet.parse("h1 Hello world")).toBe("<h1>Hello world</h1>");
});
