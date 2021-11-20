import {AdvancedCalculator, Calculator} from "./day18";

describe('Part 1', () => {
    const data: {expression: string, result: number}[] = [
        {expression: '1 + (2 * 3) + (4 * (5 + 6))', result: 51},
        {expression: '2 * 3 + (4 * 5)', result: 26},
        {expression: '5 + (8 * 3 + 9 + 3 * 4 * 3)', result: 437},
        {expression: '5 * 9 * (7 * 3 * 3 + 9 * 3 + (8 + 6 * 4))', result: 12240},
        {expression: '((2 + 4 * 9) * (6 + 9 * 8 + 6) + 6) + 2 + 4 * 2', result: 13632},
    ];

    describe.each(data)(`Expression`, (exp) => {
        it(`${exp.expression} = ${exp.result}`, () => {
            expect(Calculator.solve(exp.expression)).toBe(exp.result);
        })
    })
})

describe('Part 2', () => {
    const data: {expression: string, result: number}[] = [
        {expression: '1 + (2 * 3) + (4 * (5 + 6))', result: 51},
        {expression: '2 * 3 + (4 * 5)', result: 46},
        {expression: '5 + (8 * 3 + 9 + 3 * 4 * 3)', result: 1445},
        {expression: '5 * 9 * (7 * 3 * 3 + 9 * 3 + (8 + 6 * 4))', result: 669060},
        {expression: '((2 + 4 * 9) * (6 + 9 * 8 + 6) + 6) + 2 + 4 * 2', result: 23340},
    ];

    describe.each(data)(`Expression`, (exp) => {
        it(`${exp.expression} = ${exp.result}`, () => {
            expect(AdvancedCalculator.solve(exp.expression)).toBe(exp.result);
        })
    })
})