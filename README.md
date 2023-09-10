# BankNoteX

BankNoteX is a Pocketmine-MP plugin that allows players to convert in-game currency into banknotes for safekeeping. This plugin supports popular economy plugins like BedrockEconomy and EconomyAPI.

## Features

- Convert in-game currency into banknotes.
- Support for BedrockEconomy and EconomyAPI.
- Simple and user-friendly commands.
- Redeem banknotes by holding them in hand.
- Customizable configuration to tailor the plugin to your server's needs.

## Commands

- `/banknote (amount) (count)` - Convert in-game currency into banknotes.

## Installation

1. Download the BankNoteX plugin.
2. Place the plugin in your Pocketmine-MP `plugins` directory.
3. Configure the `config.yml` file as needed (see below).
4. Restart your server.

## Configuration (`config.yml`)

### Banknote Command Configuration

- `name` - Set the command name.
- `aliases` - Define command aliases for easy access.
- `usage` - Specify the command usage format.
- `description` - Provide a description of the command.

### Messages Configuration

- `prefix` - Customize the prefix for plugin messages.
- `no-console` - Message displayed when someone tries to use a command from the console.
- `invalid-amount` - Message for invalid input when creating banknotes.
- `no-money` - Message when a player doesn't have enough money to create a banknote.
- `purchased` - Confirmation message when successfully creating banknotes.
- `inv-full` - Message when a player's inventory is full.
- `redeem-success` - Message when successfully redeeming a banknote.

### Banknote Item Settings

- `itemId` - Set the desired item ID for banknotes (e.g., 'PAPER').
- `itemName` - Customize the display name of banknotes.
- `itemLore` - Define lore lines for banknotes. Customize as needed.
- `itemTag` - Customize the NBT tag for banknotes if required.

## Lock/Unlock System (TODO)

We have plans to implement a lock/unlock system for banknotes to enhance security. Stay tuned for updates!

## Contributing

We welcome contributions to BankNoteX! If you have ideas, bug reports, or code improvements, please [submit an issue](https://github.com/Amitminer/BankNoteX/issues) or create a pull request.

## License

BankNoteX is licensed under the [MIT License](LICENSE).

## Support or Contact

If you have questions, need support, or want to reach out to the developer, you can [open an issue](https://github.com/Amitminer/BankNoteX/issues) on GitHub.

---