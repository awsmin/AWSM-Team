/**
 * config file for development
 * ---------------------------
 * @package awsm-team
 */

"use strict";

const path = require("path");
const DEV_URL = process.env.DEV_URL || "localhost";
const NODE_ENV = process.env.NODE_ENV || "development";

module.exports = {
	previewURL: DEV_URL,
	debug: NODE_ENV == "development" ? true : false,
	style: {
		dir: './css/',
		public : {
			src: "./css/public/",
			dest: "./css/",
			outputName: "team.css"
		}
	},
	scripts: {
		public: {
			src: "./js/public/",
			dest: "./js/",
			outputName: "team.js"
		}
	},
	translation: {
		domain: "awsm-team",
		package: "AWSM Team",
		team: "AWSM innovations <hello@awsm.in>",
		dest: "./language/awsm-team.pot"
	}
};
