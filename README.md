## State Machine
 - This is a software module for generating an FSM.
 - The definition (configuration) of the Machine is in `'config/app.php'`
 - The input is a string of ones and zeros representing an unsigned binary integer
 - According to an input and desired settings, the program will produce a defined result.
 - It was created some tests based on the ‘mod-three’ procedure as you can see in `StateMachineAPITest.testModThree`
 - Ex.: (using default mod3 sample)
 ```
 #php index.php 11101
  2
```
Where `11101` is equivalent of 29, and 29 mod 3 = 2.

### API Functions
The API operations are avaialbe in `StateMachineAPI`
| Function | Description    |
| :---:   | :---: | 
| `createMachine` | Create the state machine encapsulated  | 
| `processInput` | Process the input string returning an output. You need to create the machine first.  | 
| `processInputDebuging` | Same as above, but returning step by step state result  | 
| `execute` | Create the state machine, process the input and return the ouput in one single command  |
| `output` | Return current state output  |  
| `currentState` | Return current state name |  

### Usage

```
$stateMachine = new StateMachineAPI();
echo $stateMachine->execute($argv[1]);
```
or 
```
$stateMachine = new StateMachineAPI();
$stateMachine->createMachine();
echo $stateMachine->processInput($argv[1]);
```
You can test it executing the sample index.php file:
  - Inside the container: `php index.php 10101` 
  - Outside Container: `docker-compose exec web php index.php 10101`

## Configuration File
**The configuration file is in 'config/app.php'.**

A finite automaton (FA) is a 5-tuple (Q,Σ,q0,F,δ) where
In the configuration file you can find the 5-tuple (Q,Σ,q0,F,δ) plus the output map in an array.
   - 'states': representing Q, a set of finite states
   - 'initial-state': representing q0, the initial state of the the machine
   - 'final-states': representing F, the set of accepting/final states
   - 'alphabet': representing Σ, the alphabet used in the machine
   - 'transitions': representing δ, the transition function used in the machine
      - The transition function is prepresented by an array of arrays informing all transitions from a state

      Ex.: 
```
    'transitions' => [
      'S0' => ['0' => 'S0', '1' => 'S1'],
      'S1' => ['0' => 'S2', '1' => 'S0'],
      'S2' => ['0' => 'S1', '1' => 'S2'],],
```

  If the current state is S1 and the processed input is '1', the next state is S0. If the next input is 0, the next state is S0.

   - 'output-map': It is a key value array representing the output according to the final state.
   Ex.:
   ```
   'output-map' => ['S0' => 0, 'S1' => 1, 'S2' => 2],
   ```
   If the final state is S0, the output is 0. If the final state is S1, the output is 1.


   ## Basic Requirements
1. The system will receive an input string:
2. The system will have 5 config inputs:
   - Q = States set
   - Σ = Alphabet
   - q0 = Initial state
   - F = Final states
   - δ  = Transition map
3. Initial state q0 should be in the states set Q
4. Final states F should be in Q
5. The transition function δ should contain only states in Q and elements from the alphabet Σ.
6. Final states should be in F
7. State changes should follow the transition function

Edge cases:
 - States without transitions: ignore it
 - Dead end states: return error in case of further follow transitions
 - Repetitive/misleading state transition: (not valid scenario - we can not have duplicated input keys)
 - Infinite loop transitions inputs (not valid scenario)

## About the code

### Data Structure
The data structure used in the solution was `Hashmap`.
Used in:
 - StateMachine: find/store the proper state
 - State: find/store the proper transition (transition function)

### Classes
![](./state-machine-class-diagram.svg) 
 - StateMachineAPITest: It is the interface with common functions to execute and create the state machine.
 - StateMachine: Entity responsible to process the inputs redirects to a next state.
 - State: Entity manipulated by the state machine.
 - Transition: Value object representing a state transition to another.


### Code style
Using [oskarstark](https://github.com/OskarStark/php-cs-fixer-ga) to autofix phpcs
```
docker run --rm -it -w=/app -v ${PWD}:/app oskarstark/php-cs-fixer-ga:latest
```

## Requirements to run
  - Docker
 
 ## How to Run 
 1. `docker-compose up -d`
 2. `docker-compose exec web bash` (command to get inside the container)
 3. `composer install` (inside container)
 4. `php index.php 10101`

## Tests
  - Inside the container: `vendor/bin/phpunit --testdox` or;
  - Outside Container: `docker-compose exec web vendor/bin/phpunit --testdox`

```
# vendor/bin/phpunit --testdox
PHPUnit 10.0.0 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.1.25
Configuration: /var/www/html/phpunit.xml

............................                                      28 / 28 (100%)

Time: 00:00.462, Memory: 8.00 MB

State Machine API (Tests\StateMachineAPI)
 ✔ Mod three with "110"
 ✔ Mod three with "1101"
 ✔ Mod three with "1110"
 ✔ Mod three with empty·input
 ✔ Mod three with "0"
 ✔ Mod three with "1"
 ✔ Mod three with big·number
 ✔ Processing input
 ✔ Output with no input
 ✔ Dead end state
 ✔ Not accepeted final state
 ✔ Input is not a string
 ✔ Process input with no machine
 ✔ Output with no machine
 ✔ Invalid initial state machine

Configuration Manager (Tests\ConfigurationManager)
 ✔ Set and get final states

State Machine (Tests\StateMachine)
 ✔ Start without states
 ✔ Invalid initial state machine
 ✔ Missing output
 ✔ State without output
 ✔ Add transitions without state
 ✔ Add transitions to states
 ✔ Invalid process input
 ✔ Process input

State (Tests\State)
 ✔ Create state
 ✔ Process wrong input
 ✔ Process input

Transitions (Tests\Transitions)
 ✔ Create transition

OK (28 tests, 47 assertions)
```
