<h1 align="center">
  <br>
  <a href="http://www.amitmerchant.com/electron-markdownify"><img src="https://raw.githubusercontent.com/antharuu/Velvet/master/public/logo.png" alt="Markdownify" width="200"></a>
  <br>
  Velvet
  <br>
</h1>

<h4 align="center">A simple, fast and customizable HTML preprocessor, 
 inspired by <a href="https://pugjs.org/api/getting-started.html" target="_blank">pug</a>.</h4>

<p align="center">
  <a href="https://github.com/antharuu/Velvet">
  <img alt="GitHub last commit" src="https://img.shields.io/github/last-commit/antharuu/Velvet?style=for-the-badge">
  </a>
  <a href="https://badge.fury.io/js/velvet-lang">
    <img alt="npm" src="https://img.shields.io/npm/v/velvet-lang?style=for-the-badge">
  </a>
  <a href="https://www.npmjs.com/package/velvet-lang">
  <img alt="npm bundle size (version)" src="https://img.shields.io/bundlephobia/min/velvet-lang/*?style=for-the-badge">
  </a>
  </a>
</p>

> ⚠️ Velvet is still under development and many features of the final version are not yet present. It is possible that the syntax or the way Velvet is used will change in future versions.

## Installation

To use Velvet you just have to install it with your favorite package manager:

```bash
# NPM
$ npm install velvet-lang --save-dev

# Yarn
$ yarn add velvet-lang --dev

# PNPM
$ pnpm add velvet-lang --save-dev
```

## How To Use

```js
import Velvet from "velvet-lang";

const html = Velvet.parse("h1 Hello world");
```

## Syntaxe

> **Note**
> A website with documentation on the syntax will be released once the project is more progressed.

Simple examples

```pug
h1 Hello world
// <h1>Hello world</h1>


h1 Hello
  span world
// <h1>Hello <span>world</span></h1>

h1(data-test="title") Hello world
// <h1 data-test="title">Hello world</h1>
```
