# CRYPTO PAIR API APP
__________________________________________________________________________________________________________________
Command line application that accepts max 3 user inputs and displays the value of chosen cryptocurrency denominated in chosen currency.
This is a learning project, using open coingecko API



## The accepted inputs:
__________________________________________________________________________________________________________________
- Help 
- List
- Price
- Cryptocurrency TAG ie. BTC
- Currency TAG ie. USD





### 1. The right way to use the program:
__________________________________________________________________________________________________________________
- There are 3 right ways to use the program
* As a user I want to know the price of a cryptocurrency denominated in a fiat currency. I write 'price BTC USD', and get:
> The price of BTC is ?? USD. 
* BTC And USD will be used as generic examples of cripto and fiat currencies from now on.

* I want to know which currencies and cryptocurrencies the program supports, i type 'list', and get the list of all supported currencies
* I don't know what to do with the program and type in 'help', I get short instructions on how to use the program and what the available commands are:
> After file name input either >list< to get a list of supported currencies or >price< >FiatCurrency< TAG >criptoCurrency< TAG
* As a side product the program supports the any fiat to fiat pairs as well.

### 2. The wrong way to use the program:
__________________________________________________________________________________________________________________
* No input or any unsupported input will print out help
* Input anything other than 'help', 'price', 'list' as 1st entry, will echo help txt: 
> After file name input either >list< to get a list of supported currencies or >price< >FiatCurrency< TAG >criptoCurrency< TAG
* After you input 'list' or 'help' as the 1st user input the program will not look for the 2nd, 3rd etc... user input.
* Input 'price' wihout the 2nd and/or 3rd output will tell the user: 
> After price, enter criptoCurrency and currency TAG'
- Input 'price' followed by:
  * BTC, BTC: If the 2nd and 3rd entry are the same, it will tell the user: >You have entered two of the same currencies, input different currencies.
  * USD, BTC: Entering the currency TAG 1st will tell the user: You have entered a unsupported currency pair.
  * NBB, SDF: Entering atleast 1 TAG that is not on the list of supported currencies will tell the user:The currency pair you have entered is not on the list of supported currencies
  * EUR, USD: Entering 2 currency pairs will corretly display the value of currency 1 denominated in currency 2, this is not the intended use, but a by-product
  * EUR, aW1BTC: If atleast 1 of the entered TAGSs are longer than 5 characters, it will tell the user: User input error - no input can be longer than 5 characters
  * BTC, ETH: Entering 2 cryptocurrencie TAG's will tell the user: You have entered a unsupported currency pair
* Entering a 4th, 5th etc. input is irrelevant as the program does not look for it.

### 3. The absurd way to use the program
__________________________________________________________________________________________________________________
* Let me know if you find one