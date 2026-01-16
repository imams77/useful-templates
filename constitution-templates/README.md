# Constitution Templates

A collection of prompts, templates, and memory files to help you define a project's "constitution" — guiding principles, workflows, and AI prompts to keep work consistent and high-quality. These files are intended to be copied or referenced from your project repository and adapted to your team and tooling.

## Quick start ✅

1. Copy this folder (or the subfolders you need) into your project, e.g. `cp -r constitution-templates .` or add it as a submodule.
2. Inspect and customize the prompts in `.github/prompts/` to match your project's tone and needs.
3. Copy templates and memory files from `.specify/` into your project's `.specify/` directory and edit them to reflect your project rules.
4. Optional: add references to these templates in your CI/CD or automation workflows to standardize automated prompts.

## What's inside 🔧

- `.github/prompts/` — ready-to-use AI prompts that can be used in GitHub workflows or local tooling.
- `.specify/templates/` — markdown templates (plans, specs, checklists, tasks) to standardize artifacts.
- `.specify/memory/` — baseline constitution and memory files you can adapt for your project.
- `.vscode/` — recommended workspace settings for working with these templates.

## Integration tips 💡

- Keep templates in a top-level folder so automation and contributors can easily find them.
- When customizing, preserve the original file names or add a `.template.md` suffix to make intent clear.
- Use small, incremental changes and open PRs so the rest of your team can review and align on the constitution.

## Contributing ✨

Send a PR with improvements or new templates. Keep changes focused and include examples of how you used the template in a real project.

---

> Note: These are starter templates — always adapt them to your project's needs and policies.
