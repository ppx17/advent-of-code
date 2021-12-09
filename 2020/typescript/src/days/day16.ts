import {Day, product, sum} from "../aoc";

export class Day16 extends Day {
    private rules: Rule[];
    private tickets: Ticket[];

    day = (): number => 16;

    part1 = (): string => this.tickets
        .map(t => t.filter((f: number) => !this.matchesAnyRule(f)).reduce(sum, 0))
        .reduce(sum)
        .toString();

    part2(): string {
        const validTickets = this.tickets
            .filter(t => !this.invalidTicket(t));

        return this.reduceRuleOptions(this.ruleSetsForFields(validTickets))
            .filter(ruleForField => ruleForField.rule.name.startsWith('departure'))
            .map(ruleForField => this.myTicket()[ruleForField.field])
            .reduce(product, 1)
            .toString();
    }

    setup() {
        this.rules = this.input
            .map(l => l.match(/^(?<field>[a-z ]+): (?<r1min>\d+)-(?<r1max>\d+) or (?<r2min>\d+)-(?<r2max>\d+)$/))
            .filter(r => r)
            .map((r): Rule => ({
                name: r.groups.field,
                firstRange: {min: Number(r.groups.r1min), max: Number(r.groups.r1max)},
                secondRange: {min: Number(r.groups.r2min), max: Number(r.groups.r2max)},
            }));

        this.tickets = this.input
            .slice(this.input.indexOf('nearby tickets:') + 1)
            .map((l): Ticket => l.split(',').map(n => Number(n)));
    }

    private ruleSetsForFields = (validTickets: Ticket[]): RuleSetForField[] => [...validTickets[0].keys()]
        .map(field => ({field, rules: this.findRulesetValidForField(validTickets, field)}));

    private reduceRuleOptions(ruleSetsForFields: RuleSetForField[], result: RuleForField[] = []): RuleForField[] {
        ruleSetsForFields.forEach(rulesetForField => {
            if (rulesetForField.rules.size !== 1) return;

            const [rule] = rulesetForField.rules;
            result.push({rule, field: rulesetForField.field});

            ruleSetsForFields.forEach(ruleset => ruleset.rules.delete(rule));
        });

        return result.length === this.rules.length ? result : this.reduceRuleOptions(ruleSetsForFields, result);
    }

    private findRulesetValidForField(validTickets: Ticket[], field: number): Set<Rule> {
        const result = new Set<Rule>();

        this.rules.forEach(rule => {
            if (!this.hasInvalidTicket(validTickets, field, rule)) result.add(rule);
        });

        return result;
    }

    private hasInvalidTicket(validTickets: Ticket[], field: number, rule: Rule) {
        return validTickets.find(ticket => !Day16.matchesRule(ticket[field], rule)) !== undefined;
    }

    private myTicket = (): number[] => this.input[this.input.indexOf('your ticket:') + 1]
        .split(',')
        .map(n => Number(n));

    private invalidTicket = (ticket: Ticket): boolean => ticket.find(f => !this.matchesAnyRule(f)) !== undefined;

    private matchesAnyRule = (field: number): boolean => this.rules.find(r => Day16.matchesRule(field, r)) !== undefined;

    private static matchesRule = (value: number, r: Rule): boolean =>
        this.matchesRange(value, r.firstRange) || this.matchesRange(value, r.secondRange);

    private static matchesRange = (value: number, r: Range): boolean => value >= r.min && value <= r.max;
}

type Rule = { name: string, firstRange: Range, secondRange: Range };
type Range = { min: number, max: number };
type Ticket = number[];
type RuleForField = { field: number, rule: Rule };
type RuleSetForField = { field: number, rules: Set<Rule> };