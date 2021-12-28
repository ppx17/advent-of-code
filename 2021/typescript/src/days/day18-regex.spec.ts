import {snailAdd, explode, magnitude, reduce, Result, split} from "./day18-regex";

describe('explode', () => {
    test.each([
        ['[[[[[9,8],1],2],3],4]', '[[[[0,9],2],3],4]'],
        ['[7,[6,[5,[4,[3,2]]]]]', '[7,[6,[5,[7,0]]]]'],
        ['[[6,[5,[4,[3,2]]]],1]', '[[6,[5,[7,0]]],3]'],
        ['[[3,[2,[1,[7,3]]]],[6,[5,[4,[3,2]]]]]', '[[3,[2,[8,0]]],[9,[5,[4,[3,2]]]]]'],
        ['[[3,[2,[8,0]]],[9,[5,[4,[3,2]]]]]', '[[3,[2,[8,0]]],[9,[5,[7,0]]]]'],
    ])('explode(%s)', (input: string, expected: string) => {
        expect(explode(input)).toStrictEqual({output: expected, hasPerformed: true});
    })
})

describe('split', () => {
    test.each([
        ['[[[[0,7],4],[15,[0,13]]],[1,1]]', '[[[[0,7],4],[[7,8],[0,13]]],[1,1]]'],
        ['[[[[0,7],4],[[7,8],[0,13]]],[1,1]]', '[[[[0,7],4],[[7,8],[0,[6,7]]]],[1,1]]'],
    ])('explode(%s)', (input: string, expected: string) => {
        expect(split(input)).toStrictEqual({output: expected, hasPerformed: true});
    })
});

describe('reduce', () => {
    test('sample reduce', () => {
        const input = '[[[[[4,3],4],4],[7,[[8,4],9]]],[1,1]]';
        expect(reduce(input)).toBe('[[[[0,7],4],[[7,8],[6,0]]],[8,1]]');
    })
});

describe('add', () => {
    test.each([
        [
            '[[[[7,0],[7,7]],[[7,7],[7,8]]],[[[7,7],[8,8]],[[7,7],[8,7]]]]',
            '[7,[5,[[3,8],[1,4]]]]',
            '[[[[7,7],[7,8]],[[9,5],[8,7]]],[[[6,8],[0,8]],[[9,9],[9,0]]]]'
        ]
    ])('add(%s, %s)', (a: string, b: string, expected: string) => {
        expect(snailAdd(a,b,)).toBe(expected);
    })
});

describe('magnitude', () => {
    test.each([
        ['[[1,2],[[3,4],5]]', 143],
        ['[[[[0,7],4],[[7,8],[6,0]]],[8,1]]', 1384],
        ['[[[[1,1],[2,2]],[3,3]],[4,4]]', 445]
    ])('magnitude(%s)', (input: string, expected: number) => {
        expect(magnitude(input)).toBe(expected);
    })
})
