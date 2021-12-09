import {Day, product, sum} from "../aoc";

export class Day18 extends Day {
    day = (): number => 18;

    part1 = (): string => this.input.map(l => Calculator.solve(l)).reduce(sum).toString();

    part2 = (): string => this.input.map(l => AdvancedCalculator.solve(l)).reduce(sum).toString();

}

export class Calculator {
    public static solve(expression: string): number {
        return Number(this.solveRecursive(expression));
    }

    protected static solveRecursive(expression: string): string {
        expression = Calculator.solveParentheses(expression, this.solveRecursive.bind(this));
        return Calculator.addAndMultiply(expression);
    }

    protected static solveParentheses(expression: string, resolver: (expression: string) => string): string {
        return this.apply(expression, /\(([^)^(]*)\)/, resolver);
    }

    private static addAndMultiply(expression: string): string {
        return this.apply(expression, /(\d+) ([*+]) (\d+)/, (a, op, b): string => {
            return {'+': sum, '*': product}[op](Number(a), Number(b)).toString();
        });
    }

    protected static apply(expression: string, pattern: RegExp, callback: (...groups: string[]) => string): string {
        do {
            expression = expression.replace(pattern, (...params: string[]): string => {
                return callback(...params.slice(1, params.length - 2));
            });
        } while (expression.match(pattern));

        return expression;
    }
}

export class AdvancedCalculator extends Calculator {
    protected static solveRecursive(expression: string): string {
        expression = this.solveParentheses(expression, this.solveRecursive.bind(this));
        expression = this.add(expression);
        return this.multiply(expression);
    }

    protected static add = (expression: string): string => this.operator(expression, /(\d+) \+ (\d+)/, sum);

    protected static multiply = (expression: string): string => this.operator(expression, /(\d+) \* (\d+)/, product);

    protected static operator(expression: string, pattern: RegExp, operator: (a: number, b: number) => number): string {
        return this.apply(expression, pattern, (a, b): string => {
            return operator(Number(a), Number(b)).toString();
        });
    }
}
