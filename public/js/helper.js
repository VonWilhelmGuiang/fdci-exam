const ExtractErrors = (err) => {
    let errors = []
    Object.entries(err).forEach(
        ([key, value]) => errors.push(value)
    );
    return errors
}