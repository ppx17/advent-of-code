import {Calculator, Day18, Pair, Parser, Scalar} from "./day18";

describe('Day 18', () => {
    describe('parser', () => {
        test('double digits', () => {
            const s = Parser.parseLine('[0,13]');
            expect(s.right.magnitude()).toBe(13);
        });
    });

    describe('explode', () => {
        test('sample 1', () => {
            const calculator = new Calculator();
            const s = Parser.parseLine('[[[[[9,8],1],2],3],4]');
            expect(calculator.explode(s).toString()).toBe('[[[[0,9],2],3],4]');
        })
        test('sample 2', () => {
            const calculator = new Calculator();
            const s = Parser.parseLine('[7,[6,[5,[4,[3,2]]]]]');
            expect(calculator.explode(s).toString()).toBe('[7,[6,[5,[7,0]]]]');
        })
        test('sample 3', () => {
            const calculator = new Calculator();
            const s = Parser.parseLine('[[6,[5,[4,[3,2]]]],1]');
            expect(calculator.explode(s).toString()).toBe('[[6,[5,[7,0]]],3]');
        })
        test('sample 4', () => {
            const calculator = new Calculator();
            const s = Parser.parseLine('[[3,[2,[1,[7,3]]]],[6,[5,[4,[3,2]]]]]');
            expect(calculator.explode(s).toString()).toBe('[[3,[2,[8,0]]],[9,[5,[4,[3,2]]]]]');
        })
        test('sample 5', () => {
            const calculator = new Calculator();
            const s = Parser.parseLine('[[3,[2,[8,0]]],[9,[5,[4,[3,2]]]]]');
            expect(calculator.explode(s).toString()).toBe('[[3,[2,[8,0]]],[9,[5,[7,0]]]]');
        })
        test('sample 6', () => {
            const calculator = new Calculator();
            const s = Parser.parseLine('[[[[0,7],4],[[7,8],[0,[6,7]]]],[1,1]]');
            expect(calculator.explode(s).toString()).toBe('[[[[0,7],4],[[7,8],[6,0]]],[8,1]]');
        })
    })

    describe('split', () => {
        test('sample 1', () => {
            const calculator = new Calculator();
            const s = Parser.parseLine('[[[[0,7],4],[[7,8],[0,13]]],[1,1]]');
            expect(calculator.split(s).toString()).toBe('[[[[0,7],4],[[7,8],[0,[6,7]]]],[1,1]]');

        });
        test('sample 2', () => {
            const calculator = new Calculator();
            const s = Parser.parseLine('[0,13]');
            expect(calculator.split(s).toString()).toBe('[0,[6,7]]');

        });
        test('split order 1', () => {
            const calculator = new Calculator();
            const s = Parser.parseLine('[20,13]');
            expect(calculator.split(s).toString()).toBe('[[10,10],13]');

        });
        test('split order 2', () => {
            const calculator = new Calculator();
            const s = Parser.parseLine('[[20,1],13]');
            expect(calculator.split(s).toString()).toBe('[[[10,10],1],13]');

        });

        test('basic split', () => {
            const calculator = new Calculator();
            const pair = new Pair(1);
            pair.left = new Scalar(2, 1, pair);
            pair.right = new Scalar(2, 13, pair);

            const result = calculator.split(pair) as Pair;

            expect(result.left.magnitude()).toBe(1);

            const rightPair = result.right as Pair;
            expect(rightPair.left.magnitude()).toBe(6);
            expect(rightPair.right.magnitude()).toBe(7);

            expect(rightPair.parent).toBe(pair);
        });
    })

    describe('add', () => {

        describe('small example', () => {
            test('single step', () => {
                const calculator = new Calculator();
                const result = ['[[[[4,3],4],4],[7,[[8,4],9]]]', '[1,1]']
                    .map(l => Parser.parseLine(l))
                    .reduce((a, b) => calculator.add(a, b));

                expect(result.toString()).toBe('[[[[0,7],4],[[7,8],[6,0]]],[8,1]]');
            });
        });

        describe('slightly larger example', () => {
            test('step 1', () => {
                const calculator = new Calculator();
                const result = [
                    '[[[0,[4,5]],[0,0]],[[[4,5],[2,6]],[9,5]]]',
                    '[7,[[[3,7],[4,3]],[[6,3],[8,8]]]]'
                ]
                    .map(l => Parser.parseLine(l))
                    .reduce((a, b) => calculator.add(a, b));

                expect(result.toString()).toBe('[[[[4,0],[5,4]],[[7,7],[6,0]]],[[8,[7,7]],[[7,9],[5,0]]]]');
            });
            test('step 2', () => {
                const calculator = new Calculator();
                const result = [
                    '[[[[4,0],[5,4]],[[7,7],[6,0]]],[[8,[7,7]],[[7,9],[5,0]]]]',
                    '[[2,[[0,8],[3,4]]],[[[6,7],1],[7,[1,6]]]]'
                ]
                    .map(l => Parser.parseLine(l))
                    .reduce((a, b) => calculator.add(a, b));

                expect(result.toString()).toBe('[[[[6,7],[6,7]],[[7,7],[0,7]]],[[[8,7],[7,7]],[[8,8],[8,0]]]]');
            });
            test('step 3', () => {
                const calculator = new Calculator();
                const result = [
                    '[[[[6,7],[6,7]],[[7,7],[0,7]]],[[[8,7],[7,7]],[[8,8],[8,0]]]]',
                    '[[[[2,4],7],[6,[0,5]]],[[[6,8],[2,8]],[[2,1],[4,5]]]]'
                ]
                    .map(l => Parser.parseLine(l))
                    .reduce((a, b) => calculator.add(a, b));

                expect(result.toString()).toBe('[[[[7,0],[7,7]],[[7,7],[7,8]]],[[[7,7],[8,8]],[[7,7],[8,7]]]]');
            });
            test('step 4', () => {
                const calculator = new Calculator();
                const result = [
                    '[[[[7,0],[7,7]],[[7,7],[7,8]]],[[[7,7],[8,8]],[[7,7],[8,7]]]]',
                    '[7,[5,[[3,8],[1,4]]]]'
                ]
                    .map(l => Parser.parseLine(l))
                    .reduce((a, b) => calculator.add(a, b));

                expect(result.toString()).toBe('[[[[7,7],[7,8]],[[9,5],[8,7]]],[[[6,8],[0,8]],[[9,9],[9,0]]]]');
            });
            test('step 5', () => {
                const calculator = new Calculator();
                const result = [
                    '[[[[7,7],[7,8]],[[9,5],[8,7]]],[[[6,8],[0,8]],[[9,9],[9,0]]]]',
                    '[[2,[2,2]],[8,[8,1]]]'
                ]
                    .map(l => Parser.parseLine(l))
                    .reduce((a, b) => calculator.add(a, b));

                expect(result.toString()).toBe('[[[[6,6],[6,6]],[[6,0],[6,7]]],[[[7,7],[8,9]],[8,[8,1]]]]');
            });
            test('step 6', () => {
                const calculator = new Calculator();
                const result = [
                    '[[[[6,6],[6,6]],[[6,0],[6,7]]],[[[7,7],[8,9]],[8,[8,1]]]]',
                    '[2,9]'
                ]
                    .map(l => Parser.parseLine(l))
                    .reduce((a, b) => calculator.add(a, b));

                expect(result.toString()).toBe('[[[[6,6],[7,7]],[[0,7],[7,7]]],[[[5,5],[5,6]],9]]');
            });
            test('step 7', () => {
                const calculator = new Calculator();
                const result = [
                    '[[[[6,6],[7,7]],[[0,7],[7,7]]],[[[5,5],[5,6]],9]]',
                    '[1,[[[9,3],9],[[9,0],[0,7]]]]'
                ]
                    .map(l => Parser.parseLine(l))
                    .reduce((a, b) => calculator.add(a, b));

                expect(result.toString()).toBe('[[[[7,8],[6,7]],[[6,8],[0,8]]],[[[7,7],[5,0]],[[5,5],[5,6]]]]');
            });
            test('step 8', () => {
                const calculator = new Calculator();
                const result = [
                    '[[[[7,8],[6,7]],[[6,8],[0,8]]],[[[7,7],[5,0]],[[5,5],[5,6]]]]',
                    '[[[5,[7,4]],7],1]'
                ]
                    .map(l => Parser.parseLine(l))
                    .reduce((a, b) => calculator.add(a, b));

                expect(result.toString()).toBe('[[[[7,7],[7,7]],[[8,7],[8,7]]],[[[7,0],[7,7]],9]]');
            });
            test('step 9', () => {
                const calculator = new Calculator();
                const result = [
                    '[[[[7,7],[7,7]],[[8,7],[8,7]]],[[[7,0],[7,7]],9]]',
                    '[[[[4,2],2],6],[8,7]]'
                ]
                    .map(l => Parser.parseLine(l))
                    .reduce((a, b) => calculator.add(a, b));

                expect(result.toString()).toBe('[[[[8,7],[7,7]],[[8,6],[7,7]]],[[[0,7],[6,6]],[8,7]]]');
            });
        });
    });

    describe('magnitude', () => {
        test('sample 1', () => expect(Parser.parseLine('[[1,2],[[3,4],5]]').magnitude()).toBe(143));
        test('sample 2', () => expect(Parser.parseLine('[[[[0,7],4],[[7,8],[6,0]]],[8,1]]').magnitude()).toBe(1384));
        test('sample 6', () => expect(Parser.parseLine('[[[[8,7],[7,7]],[[8,6],[7,7]]],[[[0,7],[6,6]],[8,7]]]').magnitude()).toBe(3488));
        test('sample 7', () => expect(Parser.parseLine('[[[[6,6],[7,6]],[[7,7],[7,0]]],[[[7,7],[7,7]],[[7,8],[9,9]]]]').magnitude()).toBe(4140));
    });
});