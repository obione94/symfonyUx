import React from 'react';
export default class Counter extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            count: 1,
        };
    }
    render() {
        return (
            <div>
                <span
                    onClick={() => this.setState({
                        count: this.state.count + 1,
                    })}
                >
                    <h3>{'#️'.repeat(this.state.count)}</h3>
                </span>
            </div>
        )
    }
}