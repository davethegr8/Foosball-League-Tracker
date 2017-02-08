# Foosball League Tracker

This is a simple application that I made to help me track foosball games in my office.

## Rankings

Rankings are calculated using three separate algorithms: ELO, and 2 variants of a custom algorithm I developed specifically for foosball. The average value of the rankings is displayed. 

### FoosRank

The custom ranking I created works something like this:

1. All players start at 1000
2. When entering a game, the total rank for each team is calculated as the sum of the player's ranks. 
3. A difference in rank of 100 points equates to: the higher ranked player is expected to win by 1 goal.
4. After finishing a game, a players rank changes by: performance_value +- win_points
5. For each player/team, their expected goal difference is: (their rank - the opposing team rank) / rank value difference of one goal
6. The actual difference for each side is calculated, and the expected difference is subtracted from that to obtain a final difference
7. The points each player earns for their performance is calculated as: the final difference + participation points
8. Then, some points are added or subtracted from the performance value depending on whether the player won or lost the game
9. For winners, their score is then adjusted to be the max of 0 or the previous value (to prevent punishing players for not winning by enough points)

#### Example 1: P1 - Ranked 1000 vs P2 - Ranked 1000; 10 - 9

In this base case, the players are ranked identically, and the game ends with a 1 goal vistory for Player 1. 

- Expected Diff: `[ Player 1: 0, Player 2: 0 ]`
- Actual Diff: `[ Player 1: +1, Player 2: -1 ]`
- Final Diff: `[ Player 1: +1, Player 2: -1 ]`
- Performance Points: `[ Player 1: +1, Player 2: -1 ]` + Participation points (+3) = `[ Player 1: +4, Player 2: +2 ]`
- Add or subtract points for winning (10): `[ Player 1: +14, Player 2: -8 ]`
- Adjusted Ranks: `[ Player 1: 1014, Player 2: 992 ]`

#### Example 2: P1 - Ranked 1250 vs P2 - Ranked 750; 10 - 5

In this base case, the players are ranked very differently. The game ends with a 10-5 victory for Player 1, matching what the ranking algoritm expected.

- Expected Diff: `[ Player 1: +5, Player 2: -5 ]`
- Actual Diff: `[ Player 1: +5, Player 2: -5 ]`
- Final Diff: `[ Player 1: 0, Player 2: 0 ]`
- Performance Points: `[ Player 1: 0, Player 2: 0 ]` + Participation points (+3) = `[ Player 1: +3, Player 2: +3 ]`
- Add or subtract points for winning (10): `[ Player 1: +13, Player 2: -7 ]`
- Adjusted Ranks: `[ Player 1: 1263, Player 2: 743 ]`

#### Example 3: P1 - Ranked 1500 vs P2 - Ranked 500; 10 - 5

In this case, the algorithm expects a massive (10 point) victory for Player 1. However, Player 2 performs well above expectations, and the system will reward them for performing better than expected.

- Expected Diff: `[ Player 1: +10, Player 2: -10 ]`
- Actual Diff: `[ Player 1: +5, Player 2: -5 ]`
- Final Diff: `[ Player 1: -5, Player 2: +5 ]`
- Performance Points: `[ Player 1: -5, Player 2: +5 ]` + Participation points (+3) = `[ Player 1: -2, Player 2: +8 ]`
- Add or subtract points for winning (10): `[ Player 1: +8, Player 2: -2 ]`
- Adjusted Ranks: `[ Player 1: 1508, Player 2: 498 ]`

Note: Yes, it's possible to gain points while losing a game, you have to perform vastly above expectations. Should this happen, it would be an epic game that you'd probably tell your coworkers about, which is pretty much the point of making that possible.

#### Participation points

Why reward players if they fail? Participation points are given to reward players from playing, and also to reflect skill increasing over time. While this can be abused over time (several thousand games), the potential is migitated by averaging out several ranking algorithms.

#### Configurability

In this algorithm, all the values for participation points, goal difference multiplier, win points, minimum points for winning, and the one goal difference value are all configurable. The default settings are:

```
$defaults = array(
	//the amount of points you get for playing. Makes rank have an overall upwards trend
	//makes it kinda worthwhile to play, even if you suck
	'participation_points' => 3,

	//how important the difference in goals is. 1 = not important.
	//setting higher makes larger score differences give more points.
	'goal_diff_multiplier' => 1,

	//how many points you get for winning
	'win_points' => 10,

	//how many points is the minimum for winning
	'win_min_points' => 0,

	//how many points = expected goal diff of one
	'one_goal_diff' => 100
);
```

The default values are used for one variant of the FoosRank algorithm, while the other removes the participation points, leading to a strictly performance-based rank adjustment.
