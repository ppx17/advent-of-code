import {Day, product, sum} from "../aoc";

export class Day16 extends Day {
    day = (): number => 16;

    part1 = () => this.packet.versionSum()
    part2 = () => this.packet.value()

    setup = () => this.packet = new Packet(
        this.input[0]
            .split('')
            .map(c => parseInt(c, 16).toString(2).padStart(4, '0'))
            .join('')
    );

    private packet: Packet;
}

class Packet {
    public version: number;
    public typeID: number;
    public lengthTypeID: number;
    public literalValue: number;
    public lengthInBits = 0;
    public subPackets: Packet[] = [];

    constructor(public data: string) {
        this.version = parseInt(this.data.substring(0, 3), 2);
        this.typeID = parseInt(this.data.substring(3, 6), 2);

        this.isLiteral() ? this.parseLiteral() : this.parseOperator();
    }

    public versionSum(): number {
        return this.subPackets.map(p => p.versionSum()).reduce(sum, 0) + this.version;
    }

    public value(): number {
        const subPacketValues = this.subPackets.map(p => p.value());

        switch (this.typeID) {
            case 0:
                return subPacketValues.reduce(sum, 0);
            case 1:
                return subPacketValues.reduce(product, 1);
            case 2:
                return Math.min(...subPacketValues);
            case 3:
                return Math.max(...subPacketValues);
            case 4:
                return this.literalValue;
            case 5:
                return subPacketValues[0] > subPacketValues[1] ? 1 : 0;
            case 6:
                return subPacketValues[0] < subPacketValues[1] ? 1 : 0;
            case 7:
                return subPacketValues[0] === subPacketValues[1] ? 1 : 0;
        }
    }

    public isLiteral = () => this.typeID === 4;

    private parseLiteral() {
        let data = '';
        for (let pos = 6, finished = false; !finished; pos += 5) {
            let bits = this.data.substring(pos, pos + 5);
            finished = bits[0] === '0';
            data += bits.substring(1);
            this.lengthInBits = pos + 5;
        }
        this.literalValue = parseInt(data, 2);
    }

    private parseOperator() {
        this.lengthTypeID = parseInt(this.data[6], 2);

        if (this.lengthTypeID === 0) {
            const totalSubPacketLength = parseInt(this.data.substring(7, 22), 2);
            let subPacketsData = this.data.substring(22, 22 + totalSubPacketLength);

            this.lengthInBits = 22 + totalSubPacketLength;

            do {
                const subPacket = new Packet(subPacketsData);
                subPacketsData = subPacketsData.substring(subPacket.lengthInBits);
                this.subPackets.push(subPacket);
            } while (subPacketsData.length > 0);

        } else if (this.lengthTypeID === 1) {
            const totalSubPacketCount = parseInt(this.data.substring(7, 18), 2);

            let subPacketsData = this.data.substring(18);
            this.lengthInBits = 18;

            for (let i = 0; i < totalSubPacketCount; i++) {
                const subPacket = new Packet(subPacketsData);
                subPacketsData = subPacketsData.substring(subPacket.lengthInBits);
                this.lengthInBits += subPacket.lengthInBits;
                this.subPackets.push(subPacket);
            }
        }
    }
}