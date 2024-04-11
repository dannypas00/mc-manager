module.exports = {
    root: true,
    env: {
        node: false,
    },
    extends: [
        "eslint:recommended",
        "plugin:vue/vue3-strongly-recommended",
        "@vue/standard",
        "@vue/typescript/recommended",
        "plugin:prettier/recommended",
    ],
    ignorePatterns: ["shims-vue.d.ts", "models.d.ts"],
};
