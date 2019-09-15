const neonFiles = /(Addon\/Custom\/Bricks\/Neon\/|Custom\/Neon\/Bricks\/|Environments\/Neon\/|Addon\/Custom\/Special Bricks\/Switch Neon)/;
const neonProperties = /(Style Sheet=Neon\/Neon |Style Sheet=Neon Night\/Neon |Music To Play=Neon\/Neon |Forced Power-Up=Multiply 8 Inline)/;

export default function(data) {
    if (data.match(neonFiles) || data.match(neonProperties)) {
        return {
            result: true,
            mod: 'Neon Environment',
        };
    }

    return {result: false};
}
